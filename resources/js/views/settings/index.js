import Setting from './Setting';
import Calendar from './Calendar';

export default [
    {path: '/admin/setting/general', component: Setting, name: 'setting.index'},
    {path: '/admin/setting/calendar', component: Calendar, name: 'setting.calendar'},
]