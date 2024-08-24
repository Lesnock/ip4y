import { mount } from '@vue/test-utils'
import { describe, expect, test } from 'vitest'
import ProjectList from './ProjectList.vue'
import { ProjectBuilder } from '@/Testing/Builders/ProjectBuilder'

describe('<ProjectList />', () => {
    test('Show 1 project', () => {
        const wrapper = mount(ProjectList, {
            props: {
                projects: [ProjectBuilder.aProject().build()]
            }
        })
        expect(wrapper.findAll('[data-project]').length).toBe(1)
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
        expect(wrapper.findAll('[data-project]').length).toBe(2)
    })
})