import CourseList from './CourseList';
import CourseForm from './CourseForm';
import CourseEdit from './CourseEdit';

export default [
    {path: '/admin/course/courses', component: CourseList, name: 'course.index'},
    {path: '/admin/course/create', component: CourseForm, name: 'course.create'},
    {path: '/admin/course/:id/edit', component: CourseEdit, name: 'course.edit'},
]