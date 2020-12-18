import React from 'react';

const { __ } = wp.i18n;

const SocialNetworksSave = ({ attributes }) => {
  return (
    <div className='social-links'>
      {Object.entries(attributes.socialNetworks).map((socialNetwork) => {
        let key = socialNetwork[0];
        let { visible, name, icon, intentUrl } = socialNetwork[1];
        return (
          visible && (
            <a
              key={key}
              href={intentUrl}
              target='_blank'
              rel={'noopener noreferrer'}
            >
              <button
                className={
                  'components-button is-secondary is-small has-text has-icon'
                }
              >
                <span className={`dashicons dashicons-${icon}`}></span>{' '}
                {name}
              </button>
            </a>
          )
        );
      })}
    </div>
  );
};

export default SocialNetworksSave;
