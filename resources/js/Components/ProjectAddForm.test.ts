import { mount, VueWrapper } from '@vue/test-utils'
import { describe, expect, test, vi } from 'vitest'
import ProjectAddForm from './ProjectAddForm.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import { ProjectGateway } from '@/types/Gateways'
import { nextTick } from 'vue'

function fillForm(wrapper: VueWrapper, form: ProjectAddFormDTO) {
    wrapper.find('[data-input-title]').setValue(form.title)
    wrapper.find('[data-input-description]').setValue(form.description)
    wrapper.find('[data-input-due-date]').setValue(form.dueDate)
}

function createGatewayMock(): ProjectGateway {
    return {
        add: vi.fn().mockResolvedValueOnce(ProjectBuilder.aProject().build()),
        async patch() { }
    }
}

describe('<ProjectAddForm />', () => {
    test('Sends correct data', () => {
        const form = { title: 'title', description: 'description', dueDate: '2099-01-01' }
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        fillForm(wrapper, form)
        wrapper.find('[data-input-submit-button]').trigger('click')
        expect(projectGateway.add).toHaveBeenCalledWith(form)
    })

    test('Test fields have required error', async () => {
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        expect(
            wrapper.find('[data-input-title]').classes() .some(_class => _class.includes('error'))
        ).toBeFalsy()
        expect(
            wrapper.find('[data-input-description]').classes() .some(_class => _class.includes('error'))
        ).toBeFalsy()
        expect(
            wrapper.find('[data-input-due-date]').classes() .some(_class => _class.includes('error'))
        ).toBeFalsy()
        // Submit
        wrapper.find('[data-input-submit-button]').trigger('click')
        await nextTick()
        expect(
            wrapper.find('[data-input-title]').classes() .some(_class => _class.includes('error'))
        ).toBeTruthy()
        expect(
            wrapper.find('[data-input-description]').classes() .some(_class => _class.includes('error'))
        ).toBeTruthy()
        expect(
            wrapper.find('[data-input-due-date]').classes() .some(_class => _class.includes('error'))
        ).toBeTruthy()
    })

    test('Only title field has required error', () => {
        
    })
})