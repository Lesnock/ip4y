import { mount } from '@vue/test-utils'
import { describe, expect, test } from 'vitest'
import Project from './Project.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import { TaskBuilder } from '@/Testing/Builders/TaskBuilder'

/**
 * CANDIDATO: Quando estou testando o frontend, eu procuro sempre testar o que está acontecendo no DOM,
 * sem ficar testando a implementação em si, pois se a implementação mudar ligeiramente, o teste já iria quebrar.
 */
describe('<Project />', () => {
    test('Show title', () => {
        const project = ProjectBuilder.aProject().build()
        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('[data-title]').exists()).toBeTruthy()
        expect(wrapper.find('[data-title]').text()).toContain(project.title)
    })

    test('Show description', () => {
        const project = ProjectBuilder.aProject().build()
        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('[data-description]').exists()).toBeTruthy()
        expect(wrapper.find('[data-description]').text()).toBe(project.description)
    })

    test('Show task completed 0 / 1', () => {
        const project = ProjectBuilder
            .aProject()
            .withTask(TaskBuilder.aTask().pending().build())
            .build()

        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('[data-tasks-status]').text()).toContain('0 / 1')
        expect(wrapper.find('[data-tasks-status]').text()).toContain('0 / 1')
    })

    test('Show formatted due date', () => {
        const project = ProjectBuilder.aProject().build()
        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('[data-due-date]').exists()).toBeTruthy()
        expect(wrapper.find('[data-due-date]').text()).toBe(project.due_date.toLocaleDateString())
    })
})