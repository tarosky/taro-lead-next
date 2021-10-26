/*!
 * Lead block.
 *
 * @package tsln
 * @handle tsln-lead-block
 * @deps wp-blocks, wp-i18n, wp-block-editor, wp-components, wp-data
 */

/* global TaroLeadNextBlockVars:false */

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, TextControl } = wp.components;
const { title } = TaroLeadNextBlockVars;
const { select } = wp.data;

const classNames = ( align ) => {
	const classes = [ 'taro-lead-next-link' ];
	if ( align ) {
		classes.push( `has-text-align-${align}` );
	}
	return classes.join( ' ' );
}

registerBlockType( 'tarosky/lead', {

	title: __( 'Lead to Next', 'tsln' ),

	icon: 'edit-large',

	category: 'text',

	description: __( 'A lead block which will displayed as next page link. Must precedes page break.', 'tsln' ),

	attributes: {
		text: {
			type: 'string',
			source: 'text',
			selector: '.taro-lead-next-body'
		},
		align: {
			type: 'string',
			default: '',
		},
		title: {
			type: 'string',
			default: title,
		},
	},

	edit(  { attributes, setAttributes, clientId } ) {
		const options = [
			{ value: '', label: __( 'Default', 'tsln' ) },
			{ value: 'left', label: __( 'Left', 'tsln' ) },
			{ value: 'center', label: __( 'Center', 'tsln' ) },
			{ value: 'right', label: __( 'Right', 'tsln' ) },
		];
		const isNextBlockIsPageBreak = ( id ) => {
			const allBlocks = select( 'core/block-editor' ).getBlocks();
			let index = null;
			for ( let i = 0; i < allBlocks.length; i++ ) {
				if ( allBlocks[i].clientId === id ) {
					index = i;
					break;
				}
			}
			if ( null === index ) {
				return false;
			}
			const nextBlock = allBlocks[ index + 1 ];
			if ( ! nextBlock ) {
				return false;
			}
			return 'core/nextpage' === nextBlock.name;
		}
		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Lead Setting', 'tsln' ) } initialOpen={ true }>
						<TextControl label={ __( 'Title', 'tsln' ) } value={ attributes.title }
							help={ __( 'If empty, omitted.', 'tsln' ) }
							onChange={ ( newTitle ) => setAttributes( { title: newTitle } ) } />
						<SelectControl label={ __( 'Text Alignment', 'tsln' ) } value={ attributes.align }
							options={ options }
							onChange={ ( align ) => setAttributes( { align } ) } />
					</PanelBody>
				</InspectorControls>
				<div className="taro-lead-next">
					<div className={ classNames( attributes.align ) }>

					{ ( 0 < attributes.title.length ) && (
						<p className="taro-lead-next-title">
							{ attributes.title }
						</p>
					) }
						<RichText className="taro-lead-next-body"
							tagName="p" value={ attributes.text }
							multiline={ false }
							placeholder={ __( 'Some nice lead text here.', 'tsln' ) }
							onChange={ text => setAttributes( { text } ) } />
						{ ! isNextBlockIsPageBreak( clientId ) && (
							<div className="taro-lead-next-warning">{ __( 'Next block should be page block!', 'tsln' ) }</div>
						) }
					</div>
				</div>
			</>
		);
	},

	save( { attributes } ) {
		return (
			<div className="taro-lead-next">
				<a href="%link%" className={ classNames( attributes.align ) } rel="next">
					{ ( 0 < attributes.title.length ) && (
						<p className="taro-lead-next-title">
							{ attributes.title }
						</p>
					) }
					<RichText.Content tagName="p" multiline={ false } className="taro-lead-next-body" value={ attributes.text } />
				</a>
			</div>
		)
	}
} );
