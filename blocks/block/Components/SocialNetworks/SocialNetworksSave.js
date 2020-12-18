import React from 'react';

const { __ } = wp.i18n;
const { Button } = wp.components;

const SocialNetworksSave = ({ attributes }) => {
  return (
    <div className='social-links'>
      {Object.entries(attributes.socialNetworks).map((socialNetwork) => {
        let key = socialNetwork[0];
        let { visible, name, icon, intentUrl } = socialNetwork[1];
        return (
          visible && (
            <Button
              isPrimary
              key={key}
              icon={icon}
              href={intentUrl}
              target='_blank'
            >
              {name}
            </Button>
          )
        );
      })}
    </div>
  );
};

export default SocialNetworksSave;
