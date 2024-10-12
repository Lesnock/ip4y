import toastr from "toastr"
import { delay } from "@/helpers"
import { Task, User } from "@/types"
import { TaskUpdateFormDTO } from "@/types/dto"
import { TaskGateway } from "@/types/gateways"
import { validate } from "@/validation"
import { ref } from "vue"
import { number, object, string } from "yup"

export type Props = {
    task: Task,
    users: User[]
}

export function useLogic(props: Props, gateway: TaskGateway) {
    const loading = ref(false)

    const form = ref<TaskUpdateFormDTO>({
        title: props.task.title,
        description: props.task.description,
        status: props.task.status,
        project_id: props.task.project_id,
        responsible_id: props.task.responsible_id,
        due_date: new Date(props.task.due_date).toISOString().slice(0, 10),
    })

    const schema = object({
        title: string().label('Título').required(),
        description: string().label('Descrição').required(),
        status: string().label('Status').oneOf(['pendent', 'in-progress', 'completed'], 'Status é obrigatório').required(),
        project_id: number().label('Projeto').required(),
        responsible_id: number().label('Responsável').min(1, 'Responsável é obrigatório').required(),
        due_date: string().label('Data').required(),
    }) 

    const errors = ref<Partial<TaskUpdateFormDTO>>({})

    async function submit() {
        errors.value = {}
        loading.value = true
        await delay()
        const validation = validate<TaskUpdateFormDTO>(schema, form.value)
        if (!validation.status) {
            errors.value = validation.errors
            loading.value = false
            return
        }
        const error = await gateway.update(props.task.id, form.value)
        if (error) {
            loading.value = false
            toastr.error(`Erro ao salvar tarefa: ${error}`);
            return
        }
        loading.value = false
        toastr.success('Tarefa salva com sucesso!')
    }

    return {
        loading,
        form,
        errors,
        submit
    }
}