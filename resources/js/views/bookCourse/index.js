import TeacherBookList from './TeacherBookList';
import TeacherBookForm from './TeacherBookForm';
import TeacherBookEdit from './TeacherBookEdit';
import TeacherBookApply from './TeacherBookApply';
import TeacherBookInfo from './TeacherBookInfo';

export default [
    {path: '/admin/book/books', component: TeacherBookList, name: 'book.index'},
    {path: '/admin/book/create', component: TeacherBookForm, name: 'book.create'},
    {path: '/admin/book/:id/edit', component: TeacherBookEdit, name: 'book.edit'},
    {path: '/admin/book/:id/apply', component: TeacherBookApply, name: 'book.apply'},
    {path: '/admin/book/:id/info', component: TeacherBookInfo, name: 'book.info'},
]