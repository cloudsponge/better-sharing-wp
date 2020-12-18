import React from 'react';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { Button } = wp.components;

const Preview = ({ attributes }) => {
  return (
    <Fragment>
      {attributes.emailFormControl !== 'hidden' && (
        <form className='email-form'>
          <label>Email preview</label>

          <label htmlFor='email-subject'>Subject</label>
          <input
            type='text'
            name='subject'
            id='email-subject'
            value={
              attributes.emailFormControl !== 'custom'
                ? attributes.emailSubject
                : ''
            }
            readOnly={attributes.emailFormControl === 'readonly' ? true : false}
            placeholder={
              attributes.emailFormControl === 'custom'
                ? attributes.emailSubject
                : ''
            }
          />

          <label htmlFor='email-message'>Message</label>
          <textarea
            name='email-message'
            cols='30'
            rows='4'
            value={
              attributes.emailFormControl !== 'custom'
                ? attributes.emailMessage
                : ''
            }
            readOnly={attributes.emailFormControl === 'readonly' ? true : false}
            placeholder={
              attributes.emailFormControl === 'custom'
                ? attributes.emailMessage
                : ''
            }
          />
          <Button id='email-form-submit' isSecondary>
            Send
          </Button>
        </form>
      )}
    </Fragment>
  );
};

export default Preview;
