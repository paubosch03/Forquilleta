export function cleanRating (star) {
    if (star < 1) {
        return (1)
    }
    if (star > 5) {
        return (5)
    }
    return (star)
};