import './style.scss';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

import BetterSharingOutput from './output.jsx';

registerBlockType('bswp/block', {
	title: __('Better Sharing WP'),
	description: __('CloudSponge powered sharing'),
	category: 'common',
	attributes: {},

	edit: BetterSharingOutput,

	save: ( props ) => {

	}
});