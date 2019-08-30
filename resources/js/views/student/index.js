import StudentList from './StudentList';
import StudentForm from './StudentForm';
import StudentInfo from './StudentInfo';
import StudentEdit from './StudentEdit';

export default [
    {path: '/admin/student/students', component: StudentList, name: 'student.index'},
    {path: '/admin/student/create', component: StudentForm, name: 'student.create'},
    {path: '/admin/student/:id/info', component: StudentInfo, name: 'student.info'},
    // {path: '/admin/student/:id/edit', component: StudentEdit, name: 'student.edit'},
]
