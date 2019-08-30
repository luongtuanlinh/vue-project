import ClasstList from './ClasstList';
import ClassForm from './ClassForm';
import ClassEdit from './ClassEdit';
import Students from './Students';

export default [
    {path: '/admin/class/classes', component: ClasstList, name: 'class.index'},
    {path: '/admin/class/create', component: ClassForm, name: 'class.create'},
    {path: '/admin/class/:id/edit', component: ClassEdit, name: 'class.edit'},
    {path: '/admin/class/students', component: Students, name: 'class.student'},
]
