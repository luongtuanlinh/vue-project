import FeeEdit from './FeeEdit';
import FeeForm from './FeeForm';
import FeeList from './FeeList';

export default [
    {path: '/admin/fee/fees', component: FeeList, name: 'fee.index'},
    {path: '/admin/fee/create', component: FeeForm, name: 'fee.create'},
    {path: '/admin/fee/:id/edit', component: FeeEdit, name: 'fee.edit'},
]