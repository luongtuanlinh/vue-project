import ProgramList from './ProgramList';
import ProgramForm from './ProgramForm';
import ProgramEdit from './ProgramEdit';

export default [
    {path: '/admin/program/programs', component: ProgramList, name: 'program.index'},
    {path: '/admin/program/create', component: ProgramForm, name: 'program.create'},
    {path: '/admin/program/:id/edit', component: ProgramEdit, name: 'program.edit'},
]