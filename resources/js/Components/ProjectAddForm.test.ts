import { mount, VueWrapper } from '@vue/test-utils'
import { describe, expect, test, vi } from 'vitest'
import ProjectAddForm from './ProjectAddForm.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import type { ProjectGateway } from '@/types/gateways.d.ts'
import { nextTick } from 'vue'
import { ProjectAddFormDTO } from '@/types/dto'
import { Project } from '@/types'
import { SubmitResponse } from '@/Forms/ProjectAddForm'

function fillForm(wrapper: VueWrapper, form: ProjectAddFormDTO) {
    wrapper.find('[data-input-title]').setValue(form.title)
    wrapper.find('[data-input-description]').setValue(form.description)
    wrapper.find('[data-input-due-date]').setValue(form.dueDate)
}

function createGatewayMock(response?: SubmitResponse): ProjectGateway {
    const defaultResponse: SubmitResponse = {
        error: null,
        project: ProjectBuilder.aProject().build()
    }
    return {
        store: vi.fn().mockResolvedValueOnce(response ?? defaultResponse),
        async patch() { }
    }
}

describe('<ProjectAddForm />', () => {
    test('Test title has required error', async () => {
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        expect(wrapper.find('[data-input-title]').classes().some(_class => _class.includes('error'))).toBeFalsy()
        wrapper.find('[data-input-submit-button]').trigger('click')
        await nextTick()
        expect(wrapper.find('[data-input-title]').classes().some(_class => _class.includes('error'))).toBeTruthy()
    })

    test('Test description has required error', async () => {
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        expect(wrapper.find('[data-input-description]').classes().some(_class => _class.includes('error'))).toBeFalsy()
        wrapper.find('[data-input-submit-button]').trigger('click')
        await nextTick()
        expect(wrapper.find('[data-input-description]').classes().some(_class => _class.includes('error'))).toBeTruthy()
    })

    test('Test due date has required error', async () => {
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        expect(wrapper.find('[data-input-due-date]').classes().some(_class => _class.includes('error'))).toBeFalsy()
        wrapper.find('[data-input-submit-button]').trigger('click')
        await nextTick()
        expect(wrapper.find('[data-input-due-date]').classes().some(_class => _class.includes('error'))).toBeTruthy()
    })

    test('Only title field has required error', async () => {
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        wrapper.find('[data-input-description]').setValue('description')
        wrapper.find('[data-input-due-date]').setValue('2099-01-01')
        wrapper.find('[data-input-submit-button]').trigger('click')
        await nextTick()
        expect(wrapper.find('[data-input-title]').classes().some(_class => _class.includes('error'))).toBeTruthy()
        expect(wrapper.find('[data-input-description]').classes().some(_class => _class.includes('error'))).toBeFalsy()
        expect(wrapper.find('[data-input-due-date]').classes().some(_class => _class.includes('error'))).toBeFalsy()
    })

    test('Call gateway add with correct data and emits event of add with correct data', async () => {
        const form = { title: 'title', description: 'description', dueDate: '2099-01-01' }
        const responseProject = { error: null, project: ProjectBuilder.aProject().build() }
        const projectGateway = createGatewayMock(responseProject)
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        fillForm(wrapper, form)
        wrapper.find('[data-input-submit-button]').trigger('click')
        expect(projectGateway.store).toHaveBeenCalledWith(form)
        await nextTick()
        expect(wrapper.emitted()).toHaveProperty('add')
        const emitFirstArg = (wrapper.emitted().add[0] as object[])[0]
        expect(emitFirstArg).toEqual(responseProject.project)
    })
})