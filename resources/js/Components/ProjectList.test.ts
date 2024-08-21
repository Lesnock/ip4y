import { mount } from '@vue/test-utils'
import { describe, expect, test } from 'vitest'
import ProjectList from './ProjectList.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'

describe('<ProjectList />', () => {
    test('Show empty info if no projects', () => {
        const wrapper = mount(ProjectList, {
            props: {
                projects: []
            }
        })
        expect(wrapper.find('.empty').exists()).toBeTruthy()
    })

    test('Dont show empty info if it has projects', () => {
        const wrapper = mount(ProjectList, {
            props: {
                projects: [ProjectBuilder.aProject().build()]
            }
        })
        expect(wrapper.find('.empty').exists()).toBeFalsy()
    })

    test('Show 1 project', () => {
        const wrapper = mount(ProjectList, {
            props: {
                projects: [ProjectBuilder.aProject().build()]
            }
        })
        expect(wrapper.findAll('.project').length).toBe(1)
    })

    test('Show 2 projects', () => {
        const wrapper = mount(ProjectList, {
            props: {
                projects: [
                    ProjectBuilder.aProject().build(),
                    ProjectBuilder.aProject().build(),
                ]
            }
        })
        expect(wrapper.findAll('.project').length).toBe(2)
    })
})