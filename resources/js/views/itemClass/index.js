import ItemClassList from './ItemClassList';
import ItemClassForm from './ItemClassForm';
import ItemClassEdit from './ItemClassEdit';

export default [
    {path: '/admin/class/classes', component: ItemClassList, name: 'item.class.index'},
    {path: '/admin/class/create', component: ItemClassForm, name: 'item.class.create'},
    {path: '/admin/class/:id/edit', component: ItemClassEdit, name: 'item.class.edit'},
]