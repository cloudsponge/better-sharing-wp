import React from 'react';

const { __ } = wp.i18n;
const { Fragment } = wp.element;

const EmailInputSave = ({ attributes, setAttributes }) => {
  return (
    <div className='email-input'>
      <label htmlFor='email-input'>Email addresses to share with</label>
      <input
        id='email-input'
        className='email-input'
        val={attributes.emailInput}
      />

      <small>
        Enter multiple email addresses by separating them with a comma
      </small>
    </div>
  );
};

export default EmailInputSave;
