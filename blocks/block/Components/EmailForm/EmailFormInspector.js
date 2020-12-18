import React from 'react';

const { __ } = wp.i18n;
const {
  TextareaControl,
  TextControl,
  PanelBody,
  PanelRow,
  RadioControl,
} = wp.components;
const { Fragment } = wp.element;

const Inspector = ({
  attributes,
  setAttributes,
  className,
  onControlChange,
}) => {
  return (
    <PanelBody
      title={__('Email form')}
      initialOpen={false}
      className={`${className}-panel-body`}
    >
      <PanelRow className='row-radiocontrol'>
        <RadioControl
          options={[
            {
              label: 'Hidden',
              value: 'hidden',
            },
            {
              label: 'Read-only',
              value: 'readonly',
            },
            {
              label: 'Custom',
              value: 'custom',
            },
          ]}
          onChange={(val) => onControlChange(val)}
          selected={attributes.emailFormControl}
        />
        <Fragment>
          <TextControl
            label='Subject'
            className='custom-input'
            value={attributes.emailSubject}
            onChange={(val) => setAttributes({ emailSubject: val })}
          ></TextControl>
          <TextareaControl
            label='Message'
            rows='4'
            className='custom-input'
            value={attributes.emailMessage}
            onChange={(val) => setAttributes({ emailMessage: val })}
          ></TextareaControl>
        </Fragment>
      </PanelRow>
    </PanelBody>
  );
};

export default Inspector;
