import TeacherList from './TeacherList';
import TeacherForm from './TeacherForm';
import TeacherEdit from './TeacherEdit';

export default [
    {path: '/admin/teacher/teachers', component: TeacherList, name: 'teacher.index'},
    {path: '/admin/teacher/create', component: TeacherForm, name: 'teacher.create'},
    {path: '/admin/teacher/:id/edit', component: TeacherEdit, name: 'teacher.edit'},
]