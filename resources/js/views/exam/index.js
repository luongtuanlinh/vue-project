import ExamList from './ExamList';
import ExamForm from './ExamForm';
import ExamEdit from './ExamEdit';
import StudentList from './StudentList';
import ExamAddStudent from './ExamAddStudent';

export default [
    {path: '/admin/exam/exams', component: ExamList, name: 'exam.index'},
    {path: '/admin/exam/create', component: ExamForm, name: 'exam.create'},
    {path: '/admin/exam/:id/edit', component: ExamEdit, name: 'exam.edit'},
    {path: '/admin/exam/:id/student', component: StudentList, name: 'exam.student'},
    {path: '/admin/exam/:id/add/student', component: ExamAddStudent, name: 'exam.add.student'},
]