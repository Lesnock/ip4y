import './bootstrap';
import '../css/app.css';
import 'toastr/build/toastr.min.css'

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { ProjectGatewayAxios } from './Gateways/ProjectGatewayAxios';
import { TaskGatewayAxios } from './Gateways/TaskGatewayAxios';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            /**
             * CANDIDATO: Injeção das instâncias das interfaces de gateway
             * Funciona mais ou menos como um Service Container do Laravel.
             */
            .provide('projectGateway', new ProjectGatewayAxios)
            .provide('taskGateway', new TaskGatewayAxios)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
