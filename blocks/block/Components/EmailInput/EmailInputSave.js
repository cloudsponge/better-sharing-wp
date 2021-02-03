import React from 'react';

const { __ } = wp.i18n;
const { Fragment } = wp.element;

// editor preview only, frontend form output by bswp-form.php
const EmailInputSave = ({ attributes, setAttributes }) => {
  return (
    <div className='email-input'>
      <label htmlFor='email-input'>Share via Email</label>
      <small>
        Invite people to use your referral code.
      </small>
      <input
        id='email-input'
        className='email-input'
        val={attributes.emailInput}
        placeholder={'To: enter contact emails separated by comma (,)'}
      />
    </div>
  );
};

export default EmailInputSave;
