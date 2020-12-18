import React from 'react';

const { __ } = wp.i18n;
const { Button } = wp.components;

const ReferralLinkSave = ({ attributes }) => {
  return (
    <div className='referral-link'>
      <label htmlFor='referral-link'>Your referral link</label>
      <input
        id='referral-link'
        value={attributes.referralLink}
        readOnly={attributes.referralLinkControl !== 'custom' ? true : false}
      />
      <Button isSecondary isSmall icon='admin-page'>
        Copy Link
      </Button>
    </div>
  );
};

export default ReferralLinkSave;
