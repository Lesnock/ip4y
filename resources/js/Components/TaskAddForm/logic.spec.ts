import { describe, expect, test, vi } from 'vitest'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import type { TaskGateway } from '@/types/gateways.d.ts'
import { useLogic } from './logic'
import { TaskBuilder } from '@/Testing/Builders/TaskBuilder'

function createGatewayMock(response?: any): TaskGateway {
    return {
        store: vi.fn().mockReturnValue(response),
        update: vi.fn(),
        async delete() {}
    }
}

describe('Task Add Form Logic', () => {
    test('Test fields are required', async () => {
        const gateway = createGatewayMock()
        const props = { project: ProjectBuilder.aProject().build(), users: [] }
        const emit = vi.fn()
        const { submit, errors } = useLogic(props, emit, gateway)
        await submit()
        expect(errors.value).toHaveProperty('title')
        expect(errors.value).toHaveProperty('description')
        expect(errors.value).toHaveProperty('due_date')
        expect(errors.value).toHaveProperty('status')
        expect(errors.value).toHaveProperty('responsible_id')
    })

    test('Call gateway update with correct data', async () => {
        const response = { error: null, task: TaskBuilder.aTask().build() }
        const gateway = createGatewayMock(response)
        const props = { project: ProjectBuilder.aProject().build(), users: [] }
        const emit = vi.fn()
        const { form, submit } = useLogic(props, emit, gateway)
        const data = {
            title: 'title',
            description: 'description',
            due_date: '2099-01-01',
            status: 'pendent',
            responsible_id: 1,
            project_id: 1
        }
        form.value = data
        await submit()
        expect(gateway.store).toHaveBeenCalledWith(data)
        expect(emit).toHaveBeenCalledWith('add', response.task)
    })
})