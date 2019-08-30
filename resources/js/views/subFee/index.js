import SubFeeList from './SubFeeList';
import SubFeeForm from './SubFeeForm';
import SubFeeEdit from './SubFeeEdit';

export default [
    {path: '/admin/sfee/sfees', component: SubFeeList, name: 'subfee.index'},
    {path: '/admin/sfee/create', component: SubFeeForm, name: 'subfee.create'},
    {path: '/admin/sfee/:id/edit', component: SubFeeEdit, name: 'subfee.edit'},
]