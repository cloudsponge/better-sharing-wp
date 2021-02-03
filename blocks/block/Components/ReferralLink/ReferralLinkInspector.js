import React from 'react';

const { __ } = wp.i18n;
const { TextControl, PanelBody, PanelRow, RadioControl } = wp.components;

const ReferralLinkInspector = ({
  attributes,
  setAttributes,
  className,
  onControlChange,
}) => {
  return (
    <PanelBody
      title={__('Referral link')}
      initialOpen={false}
      className={`${className}-panel-body`}
    >
      <PanelRow className='row-radiocontrol'>
        <RadioControl
          options={[
            {
              label: 'Permalink',
              value: 'default',
            },
            {
              label: 'Custom',
              value: 'custom',
            },
          ]}
          onChange={(val) => onControlChange(val)}
          selected={attributes.referralLinkControl}
        />
        {attributes.referralLinkControl === 'custom' && (
          <TextControl
            className='custom-input'
            value={attributes.referralLink}
            onChange={(val) => setAttributes({ referralLink: val })}
          ></TextControl>
        )}
      </PanelRow>
    </PanelBody>
  );
};

export default ReferralLinkInspector;
