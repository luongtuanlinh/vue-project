import ItemList from './ItemList';
import ItemForm from './ItemForm';
import ItemEdit from './ItemEdit';

export default [
    {path: '/admin/item/items', component: ItemList, name: 'item.index'},
    {path: '/admin/item/create', component: ItemForm, name: 'item.create'},
    {path: '/admin/item/:id/edit', component: ItemEdit, name: 'item.edit'},
]