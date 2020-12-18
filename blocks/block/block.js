import './editor.scss';
import './style.scss';

import attributes from './attributes';
import Edit from './Components/Edit';
import Save from './Components/Save';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

console.log('here');

registerBlockType('cgb/block-ea-better-sharing', {
  title: __('Better Sharing Block'),
  icon: 'share',
  category: 'common',
  attributes: attributes,
  edit: (props) => <Edit {...props} />,
  save: (props) => <Save {...props} />,
});
