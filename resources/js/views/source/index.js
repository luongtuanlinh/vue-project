import SourceList from './SourceList';
import SourceForm from './SourceForm';
import SourceEdit from './SourceEdit';

export default [
    {path: '/admin/source/sources', component: SourceList, name: 'source.index'},
    {path: '/admin/source/create', component: SourceForm, name: 'source.create'},
    {path: '/admin/source/:id/edit', component: SourceEdit, name: 'source.edit'},
]