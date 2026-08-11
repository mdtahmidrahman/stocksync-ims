export function useStatFontSize() {
  /**
   * Returns appropriate Tailwind font size class according to text length,
   * ensuring numbers/text fit seamlessly within stat card boxes without overflow.
   */
  const getFontSizeClass = (value, defaultClass = 'text-2xl sm:text-3xl font-extrabold') => {
    const str = String(value ?? '').trim();
    const len = str.length;

    if (len > 22) return 'text-xs sm:text-sm font-bold';
    if (len > 18) return 'text-sm sm:text-base font-bold';
    if (len > 14) return 'text-base sm:text-lg font-extrabold';
    if (len > 10) return 'text-lg sm:text-xl font-extrabold';
    if (len > 8)  return 'text-xl sm:text-2xl font-extrabold';
    return defaultClass;
  };

  return {
    getFontSizeClass
  };
}
