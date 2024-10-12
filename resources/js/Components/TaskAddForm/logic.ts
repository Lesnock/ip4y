import toastr from "toastr"
import { delay } from "@/helpers"
import { Project, User, VueEmitter } from "@/types"
import { TaskAddFormDTO } from "@/types/dto"
import { TaskGateway } from "@/types/gateways"
import { validate } from "@/validation"
import { ref } from "vue"
import { number, object, string } from "yup"

type Emitter = VueEmitter<'add'>

export interface Props {
    project: Project,
    users: User[]
}

export function useLogic(props: Props, emit: Emitter, gateway: TaskGateway) {
    const loading = ref(false)

    const form = ref<TaskAddFormDTO>({
        title: '',
        description: '',
        status: '',
        project_id: props.project.id,
        responsible_id: 0,
        due_date: ''
    })

    const schema = object({
        title: string().label('Título').required(),
        description: string().label('Descrição').required(),
        status: string().label('Status').oneOf(['pendent', 'in-progress', 'completed'], 'Status é obrigatório').required(),
        project_id: number().label('Projeto').required(),
        responsible_id: number().label('Responsável').min(1, 'Responsável é obrigatório').required(),
        due_date: string().label('Data').required(),
    }) 

    const errors = ref<Partial<TaskAddFormDTO>>({})

    async function submit() {
        errors.value = {}
        loading.value = true
        await delay()
        const validation = validate<TaskAddFormDTO>(schema, form.value)
        if (!validation.status) {
            errors.value = validation.errors
            loading.value = false
            return
        }
        const { error, task } = await gateway.store(form.value)
        if (error) {
            loading.value = false
            toastr.error(`Erro ao salvar tarefa: ${error}`);
            return
        }
        loading.value = false
        toastr.success('Tarefa salva com sucesso!')
        emit('add', task)
    }

    return {
        loading,
        form,
        errors,
        submit
    }
}