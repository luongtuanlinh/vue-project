import ItemBlockEdit from './ItemBlockEdit';
import ItemBlockForm from './ItemBlockForm';
import ItemBlockList from './ItemBlockList';

export default [
    {path: '/admin/item/block/blocks', component: ItemBlockList, name: 'item.block.index'},
    {path: '/admin/item/block/create', component: ItemBlockForm, name: 'item.block.create'},
    {path: '/admin/item/block/:id/edit', component: ItemBlockEdit, name: 'item.block.edit'},
]