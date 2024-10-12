import { describe, expect, test, vi } from 'vitest'
import type { TaskGateway } from '@/types/gateways.d.ts'
import { useLogic } from './logic'
import { TaskBuilder } from '@/Testing/Builders/TaskBuilder'

function createGatewayMock(): TaskGateway {
    return {
        store: vi.fn(),
        update: vi.fn(),
        async delete() {}
    }
}

describe('Task Update Form Logic', () => {
    test('Test fields are required', async () => {
        const gateway = createGatewayMock()
        const props = { task: TaskBuilder.aTask().build(), users: [] }
        const { form, submit, errors } = useLogic(props, gateway)
        form.value.title = ''
        form.value.description = ''
        form.value.due_date = ''
        form.value.responsible_id = 0
        form.value.status = ''
        await submit()
        expect(errors.value).toHaveProperty('title')
        expect(errors.value).toHaveProperty('description')
        expect(errors.value).toHaveProperty('due_date')
        expect(errors.value).toHaveProperty('responsible_id')
        expect(errors.value).toHaveProperty('status')
    })

    test('Call gateway update with correct data', async () => {
        const gateway = createGatewayMock()
        const props = { task: TaskBuilder.aTask().build(), users: [] }
        const { submit } = useLogic(props, gateway)
        await submit()
        expect(gateway.update).toHaveBeenCalled()
    })
})