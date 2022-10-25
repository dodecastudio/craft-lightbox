export const focusableElements = (target) => {
  return target.querySelectorAll('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex="0"]');
};

export const focusFirstDescendant = (element, fallback) => {
  for (var i = 0; i < element.childNodes.length; i++) {
    var child = element.childNodes[i];
    if (attemptFocus(child) || focusFirstDescendant(child)) {
      return true;
    }
  }
  if (fallback && attemptFocus(fallback)) {
    return true;
  }
  return false;
};

export const attemptFocus = (element) => {
  if (!isFocusable(element)) {
    return false;
  }

  try {
    element.focus();
  } catch (e) {
    return false;
  }

  return document.activeElement === element;
};

export const isFocusable = (element) => {
  if (element.tabIndex < 0) {
    return false;
  }

  if (element.disabled) {
    return false;
  }

  switch (element.nodeName) {
    case 'A':
      return !!element.href && element.rel != 'ignore';
    case 'INPUT':
      return element.type != 'hidden';
    case 'BUTTON':
    case 'SELECT':
    case 'TEXTAREA':
    case 'AREA':
      return true;
    default:
      return false;
  }
};

export const addMultiple = (element, classList = '') => {
  const classArray = classList != '' ? classList.split(' ') : [];
  if (classArray.length > 0) {
    element.classList.add(...classArray);
  }
};

export const removeMultiple = (element, classList = '') => {
  const classArray = classList != '' ? classList.split(' ') : [];
  if (classArray.length > 0) {
    element.classList.remove(...classArray);
  }
};

// Courtesy of @Lasnv on Stack Overflow: https://stackoverflow.com/a/8260383
export const getYouTubeID = (url) => {
  const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
  const match = url.match(regExp);
  return match && match[7].length == 11 ? match[7] : false;
};

// Courtesy of @Matilda on Stack Overflow: https://stackoverflow.com/a/11660798
export const getVimeoID = (url) => {
  const regExp = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
  const match = url.match(regExp);
  return match && match[5] ? match[5] : false;
};

export const getExtension = (url) => {
  const match = url.split(/[#?]/)[0].split('.').pop().trim();
  return match ? match : false;
};
