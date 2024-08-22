import { mount } from '@vue/test-utils'
import { describe, expect, test } from 'vitest'
import Project from './Project.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'
import { TaskBuilder } from '@/Testing/Builders/TaskBuilder'

describe('<Project />', () => {
    test('Show title', () => {
        const project = ProjectBuilder.aProject().build()
        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('.title').exists()).toBeTruthy()
        expect(wrapper.find('.title').text()).toBe(project.title)
    })

    test('Show description', () => {
        const project = ProjectBuilder.aProject().build()
        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('.description').exists()).toBeTruthy()
        expect(wrapper.find('.description').text()).toBe(project.description)
    })

    test('Show task completed 0 / 1', () => {
        const project = ProjectBuilder
            .aProject()
            .withTask(TaskBuilder.aTask().pending().build())
            .build()

        const wrapper = mount(Project, {
            props: { project }
        })

        expect(wrapper.find('.tasks-status').text()).toContain('0 / 1')
        expect(wrapper.find('.tasks-status').text()).toContain('0 / 1')
    })
})