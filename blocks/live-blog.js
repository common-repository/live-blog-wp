( function( blocks, element ) {
	var el = element.createElement;

	var blockStyle = {
		backgroundColor: '#900',
		color: '#fff',
		padding: '20px',
	};

	blocks.registerBlockType( 'lbwp/post', {
		title: 'Live Blog WP',
		icon: 'megaphone',
		category: 'widgets',
		example: {},
		edit: function() {
			return el(
				'div',
				{ style: blockStyle },
				'Live Blog WP posts will be shown here.'
			);
		}
	} );
} )( window.wp.blocks, window.wp.element );
