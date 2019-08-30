import ItemTypePriceList from './ItemTypePriceList';
import ItemTypePriceForm from './ItemTypePriceForm';
import ItemTypePriceEdit from './ItemTypePriceEdit';

export default [
    {path: '/admin/item/type/price', component: ItemTypePriceList, name: 'item.type.price.index'},
    {path: '/admin/item/type/price/create', component: ItemTypePriceForm, name: 'item.type.price.create'},
    {path: '/admin/item/type/price/:id/edit', component: ItemTypePriceEdit, name: 'item.type.price.edit'},
]