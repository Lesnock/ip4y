import toastr from 'toastr'
import { delay } from "@/helpers";
import { VueEmitter } from "@/types";
import { ProjectAddFormDTO } from "@/types/dto";
import { ProjectGateway } from "@/types/gateways";
import { validate } from "@/validation";
import { ref } from "vue";
import { object, string } from "yup";

type Emitter = VueEmitter<'add'>

export function useLogic(emit: Emitter, gateway: ProjectGateway) {
    const loading = ref(false)

    const form = ref<ProjectAddFormDTO>({
        title: '',
        description: '',
        due_date: ''
    })

    const schema = object({
        title: string().label('Título').required(),
        description: string().label('Descrição').required(),
        due_date: string().label('Data').required(),
    }) 

    const errors = ref<Partial<ProjectAddFormDTO>>({})

    async function submit() {
        errors.value = {}
        loading.value = true
        await delay()
        const validation = validate<ProjectAddFormDTO>(schema, form.value)
        if (!validation.status) {
            errors.value = validation.errors
            loading.value = false
            return
        }
        const { error, project } = await gateway.store(form.value)
        if (error) {
            loading.value = false
            toastr.error(`Erro ao salvar projeto: ${error}`);
            return
        }
        loading.value = false
        toastr.success('Projeto salvo com sucesso!')
        emit('add', project)
    }

    return {
        loading,
        form,
        errors,
        submit
    }
}