import { describe, expect, test, vi } from 'vitest'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import type { ProjectGateway } from '@/types/gateways.d.ts'
import { useLogic } from './logic'

function createGatewayMock(response?: any): ProjectGateway {
    const defaultResponse = {
        error: null,
        project: ProjectBuilder.aProject().build()
    }
    return {
        store: vi.fn().mockResolvedValueOnce(response ?? defaultResponse),
        async update() { },
        async delete() {}
    }
}

describe('Project Add Form Logic', () => {
    test('Test fields are required', async () => {
        const projectGateway = createGatewayMock()
        const emit = vi.fn()
        const { submit, errors } = useLogic(emit, projectGateway)
        await submit()
        expect(errors.value).toHaveProperty('title')
        expect(errors.value).toHaveProperty('description')
        expect(errors.value).toHaveProperty('due_date')
    })

    test('Call gateway store with correct data and emits event of add with correct data', async () => {
        const responseProject = { error: null, project: ProjectBuilder.aProject().build() }
        const projectGateway = createGatewayMock(responseProject)
        const emit = vi.fn()
        const { form, submit } = useLogic(emit, projectGateway)
        const formData = { title: 'title', description: 'description', due_date: '2099-01-01' }
        form.value = formData
        await submit()
        expect(projectGateway.store).toHaveBeenCalledWith(formData)
        expect(emit).toHaveBeenCalledWith('add', responseProject.project)
    })

})