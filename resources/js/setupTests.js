import { cleanRating } from '../js/Utils/ReviewsUtils';
import { describe, it, expect } from 'vitest';

describe('cleanRating', () => {
  it('returns 1 when the star rating is less than 1', () => {
    expect(cleanRating(0)).toBe(1);
    expect(cleanRating(-1)).toBe(1);
  });

  it('returns 5 when the star rating is greater than 5', () => {
    expect(cleanRating(6)).toBe(5);
    expect(cleanRating(10)).toBe(5);
  });

  it('returns the star rating when it is between 1 and 5', () => {
    expect(cleanRating(1)).toBe(1);
    expect(cleanRating(3)).toBe(3);
    expect(cleanRating(5)).toBe(5);
  });
});