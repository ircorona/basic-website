import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, PanelBody, PanelRow, Button } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

registerBlockType('pg/basic', {
    apiVersion: 2,
    title: __('Basic Block', 'platzigifts'),
    description: __('A basic example block for Platzi Gifts theme', 'platzigifts'),
    category: 'platzigifts',
    icon: 'smiley',
    supports: {
        html: false
    },
    attributes: {
        content: {
            type: 'string',
            default: 'Hello world'
        },
        imageUrl: {
            type: 'string',
            default: ''
        },
        imageId: {
            type: 'number',
            default: 0
        },
        imageAlt: {
            type: 'string',
            default: ''
        }
    },
    
    edit: function(props) {
        const { attributes: { content, imageUrl, imageId, imageAlt }, setAttributes } = props;
        const blockProps = useBlockProps();
        
        const handleContentChange = (newContent) => {
            setAttributes({ content: newContent });
        };

        const onSelectImage = (media) => {
            setAttributes({
                imageUrl: media.url,
                imageId: media.id,
                imageAlt: media.alt
            });
        };

        const removeImage = () => {
            setAttributes({
                imageUrl: '',
                imageId: 0,
                imageAlt: ''
            });
        };
        
        return (
            <>
                <InspectorControls>
                    <PanelBody
                        title={__('Block Settings', 'platzigifts')}
                        initialOpen={true}
                    >
                        <PanelRow>
                            <TextControl
                                label={__('Content', 'platzigifts')}
                                value={content}
                                onChange={handleContentChange}
                            />
                        </PanelRow>
                        <PanelRow>
                            <div>
                                <label>{__('Image', 'platzigifts')}</label>
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={onSelectImage}
                                        allowedTypes={['image']}
                                        value={imageId}
                                        render={({open}) => (
                                            <div>
                                                {!imageUrl ? (
                                                    <Button 
                                                        onClick={open}
                                                        variant="secondary"
                                                    >
                                                        {__('Choose Image', 'platzigifts')}
                                                    </Button>
                                                ) : (
                                                    <div>
                                                        <img 
                                                            src={imageUrl} 
                                                            alt={imageAlt}
                                                            style={{maxWidth: '100%', marginBottom: '10px'}}
                                                        />
                                                        <div>
                                                            <Button 
                                                                onClick={open}
                                                                variant="secondary"
                                                            >
                                                                {__('Replace Image', 'platzigifts')}
                                                            </Button>
                                                            <Button 
                                                                onClick={removeImage}
                                                                variant="link"
                                                                isDestructive
                                                            >
                                                                {__('Remove Image', 'platzigifts')}
                                                            </Button>
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        )}
                                    />
                                </MediaUploadCheck>
                            </div>
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    <ServerSideRender
                        block="pg/basic"
                        attributes={props.attributes}
                    />
                </div>
            </>
        );
    }
});