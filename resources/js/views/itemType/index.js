import ItemTypeList from './ItemTypeList';
import ItemTypeForm from './ItemTypeForm';
import ItemTypeEdit from './ItemTypeEdit';

export default [
    {path: '/admin/item/type/type', component: ItemTypeList, name: 'item.type.index'},
    {path: '/admin/item/type/create', component: ItemTypeForm, name: 'item.type.create'},
    {path: '/admin/item/type/:id/edit', component: ItemTypeEdit, name: 'item.type.edit'},
]