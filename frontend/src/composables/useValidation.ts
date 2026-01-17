export function useValidation() {
  const validateEmail = (email: string): string | null => {
    if (!email) {
      return 'Email is required';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      return 'Please enter a valid email address';
    }

    return null;
  };

  const validatePassword = (password: string): string | null => {
    if (!password) {
      return 'Password is required';
    } else if (password.length < 8) {
      return 'Password must be at least 8 characters';
    }

    return null;
  };

  const validatePasswordConfirmation = (password: string, confirmation: string): string | null => {
    if (password !== confirmation) {
      return 'Passwords do not match';
    }

    return null;
  };

  return {
    validateEmail,
    validatePassword,
    validatePasswordConfirmation
  };
}
