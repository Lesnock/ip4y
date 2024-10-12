import { describe, expect, test, vi } from 'vitest'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import type { ProjectGateway } from '@/types/gateways.d.ts'
import { useLogic } from './logic'

function createGatewayMock(): ProjectGateway {
    return {
        store: vi.fn(),
        update: vi.fn(),
        async delete() {}
    }
}

describe('Project Update Form Logic', () => {
    test('Test fields are required', async () => {
        const projectGateway = createGatewayMock()
        const props = { project: ProjectBuilder.aProject().build() }
        const { form, submit, errors } = useLogic(props, projectGateway)
        form.value.title = ''
        form.value.description = ''
        form.value.due_date = ''
        await submit()
        expect(errors.value).toHaveProperty('title')
        expect(errors.value).toHaveProperty('description')
        expect(errors.value).toHaveProperty('due_date')
    })

    test('Call gateway update with correct data', async () => {
        const projectGateway = createGatewayMock()
        const props = { project: ProjectBuilder.aProject().build() }
        const { submit } = useLogic(props, projectGateway)
        await submit()
        expect(projectGateway.update).toHaveBeenCalled()
    })
})