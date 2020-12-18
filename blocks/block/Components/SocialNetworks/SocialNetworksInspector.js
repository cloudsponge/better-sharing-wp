import React from 'react';

const { __ } = wp.i18n;
const { PanelBody, PanelRow, TextControl, ToggleControl } = wp.components;

const SocialNetworksInspector = ({ attributes, className, onTextChange, onToggleChange }) => {
  return (
    <PanelBody
      title={__('Social networks')}
      initialOpen={false}
      className={`${className}-panel-body`}
    >
      <PanelRow className='row-togglecontrol'>
        {Object.entries(attributes.socialNetworks).map((socialNetwork) => {
          let key = socialNetwork[0];
          let { name, visible } = socialNetwork[1];
          return (
            <div className={`${key}-inspector-controls`} key={key}>
              <ToggleControl
                label={name}
                checked={visible}
                onChange={() => onToggleChange(key)}
              />
              {visible && (
                <TextControl
                  label='Message to share:'
                  className='custom-input'
                  value={attributes.socialNetworks[key].message}
                  onChange={(val) => onTextChange(key, val)}
                  readOnly={key === 'facebook' ? true : false}
                ></TextControl>
              )}
            </div>
          );
        })}
      </PanelRow>
    </PanelBody>
  );
};

export default SocialNetworksInspector;
