import { mount, VueWrapper } from '@vue/test-utils'
import { describe, expect, test, vi } from 'vitest'
import ProjectAddForm from './ProjectAddForm.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import { ProjectGateway } from '@/types/Gateways'

function fillForm(wrapper: VueWrapper, form: ProjectAddFormDTO) {
    wrapper.find('[data-input-title]').setValue(form.title)
    wrapper.find('[data-input-description]').setValue(form.description)
    wrapper.find('[data-input-due-date]').setValue(form.dueDate)
}

describe('<ProjectAddForm />', () => {
    test('Sends correct data', () => {
        const form = { title: 'title', description: 'description', dueDate: '2099-01-01' }
        const addMethod = vi.fn().mockResolvedValueOnce(ProjectBuilder.aProject().build())
        const projectGateway = {
            add: addMethod,
            async patch() {}
        }
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        fillForm(wrapper, form)
        wrapper.find('[data-input-submit-button]').trigger('click')
        expect(addMethod).toHaveBeenCalledWith(form)
    })
})