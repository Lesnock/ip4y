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

    test('Call gateway add with correct data and emits event of add', async () => {
        const form = { title: 'title', description: 'description', dueDate: '2099-01-01' }
        const projectGateway = createGatewayMock()
        const wrapper = mount(ProjectAddForm, { global: { provide: { projectGateway } } })
        fillForm(wrapper, form)
        wrapper.find('[data-input-submit-button]').trigger('click')
        expect(projectGateway.add).toHaveBeenCalledWith(form)
        await nextTick()
        expect(wrapper.emitted()).toHaveProperty('add')
    })
})