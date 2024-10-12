import { delay } from "@/helpers";
import { Project } from "@/types";
import { ProjectUpdateFormDTO } from "@/types/dto";
import { ProjectGateway } from "@/types/gateways";
import { validate } from "@/validation";
import { ref } from "vue";
import { object, string } from "yup";

export interface Props {
    project: Project
}

/**
 * CANDIDATO: Ultimamente eu tenho gostado muito de utilizar um padrão de projeto chamado
 * Humble Object. Esse padrão diz que se você possui um objeto que é muito difícil de testar 
 * (como uma GUI ou componente Vue ou React) você deve tentar separar toda a lógica "testável" desse objeto para outro arquivo,
 * e deixar o componente Humble (humilde). Neste caso, o arquivo ProjectUpdateForm.vue será responsável por apenas exibir
 * o HTML e receber as Props, enquanto o arquivo logic.ts fica responsável por toda a lógica do componente.
 * Dessa forma, nós conseguimos testar a lógica do componente de forma independente.
 */
export function useLogic(props: Props, gateway: ProjectGateway) {
    const loading = ref(false)

    const form = ref<ProjectUpdateFormDTO>({
        title: props.project.title,
        description: props.project.description,
        due_date: new Date(props.project.due_date).toISOString().slice(0, 10),
    })

    const schema = object({
        title: string().label('Título').required(),
        description: string().label('Descrição').required(),
        due_date: string().label('Data').required(),
    })

    const errors = ref<Partial<ProjectUpdateFormDTO>>({})

    async function submit() {
        errors.value = {}
        loading.value = true
        await delay()
        const validation = validate<ProjectUpdateFormDTO>(schema, form.value)
        if (!validation.status) {
            errors.value = validation.errors
            loading.value = false
            return
        }
        const error = await gateway.update(props.project.id, form.value)
        if (error) {
            loading.value = false
            toastr.error(`Erro ao salvar projeto: ${error}`);
            return
        }
        loading.value = false
        toastr.success('Projeto salvo com sucesso!')
    }

    return {
        loading,
        form,
        errors,
        submit
    }
}