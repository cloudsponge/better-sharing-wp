const attributes = {
  referralLink: {
    type: 'string',
    default: 'default',
  },
  emailSubject: {
    type: 'string',
    default: '',
  },
  emailMessage: {
    type: 'string',
    default: '',
  },
  socialNetworks: {
    type: 'object',
    default: {
      twitter: {
        visible: true,
        name: 'Twitter',
        icon: 'twitter',
        message: '🗣 Check out this link!',
        intentUrl: '',
      },
      facebook: {
        visible: true,
        name: 'Facebook',
        icon: 'facebook',
        message: 'No custom message 😕',
        intentUrl: '',
      },
    },
  },
  // controls
  emailFormControl: {
    type: 'string',
    default: 'readonly',
  },
  referralLinkControl: {
    type: 'string',
    default: 'default',
  },
};

export default attributes;
