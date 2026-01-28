import { MODE, DEBUGGER_PRESENT } from './constants.js';
import _ from 'lodash';

// export const SYM_NO_CONSEQUENT: unique symbol = Symbol('NO_CONSEQUENT');
// export type SYM_NO_CONSEQUENT =  typeof SYM_NO_CONSEQUENT;
// export const SYM_AWAITING_STRIP: unique symbol = Symbol('AWAITING_STRIP');
// export type SYM_AWAITING_STRIP = typeof SYM_AWAITING_STRIP;
export const SYM_NO_CONSEQUENT= Symbol.for('NO_CONSEQUENT');
export const SYM_AWAITING_STRIP = Symbol.for('AWAITING_STRIP');

// export type Predicate = boolean | () => boolean;
// export type Consequent<C> = C | (() => C);
// export function evalPredicate(predicate: Predicate): boolean | never:
export function evalPredicate(predicate) {
    const testResult = (
        (typeof predicate === 'function'
            ? predicate()
            : predicate
        )
    );

    if (typeof testResult !== 'boolean') {
        throw new TypeError('predicate must be of type `boolean` or `() => boolean`');
    } else {
        return testResult;
    }
}

// export function condition(
//     predicate: Predicate
// ): boolean;
// export function condition<C, A>(
//     predicate: Predicate,
//     consequent: Consequent<C>
// ): C | SYM_NO_CONSEQUENT;
// export function condition<C, A>(
//     predicate: Predicate,
//     consequent: Consequent<C>,
//     antiConsequent: Consequent<A>
// ): C | A;
// export function condition<C, A>(
//     predicate: Predicate,
//     consequent: Consequent<C> | SYM_NO_CONSEQUENT = SYM_NO_CONSEQUENT,
//     antiConsequent: Consequent<A> | SYM_NO_CONSEQUENT = SYM_NO_CONSEQUENT
// ): boolean | C | A | SYM_AWAITING_STRIP {
export function condition(predicate, consequent = SYM_NO_CONSEQUENT, antiConsequent = SYM_NO_CONSEQUENT) {
    const result = evalPredicate(predicate);
    if (result) {
        if (consequent === SYM_NO_CONSEQUENT) return true;
        else return (typeof consequent === 'function') ? consequent() : consequent;
    } else {
        if (antiConsequent === SYM_NO_CONSEQUENT) {
            if (consequent === SYM_NO_CONSEQUENT) return false;
            else return SYM_AWAITING_STRIP;
        } else {
            return (typeof antiConsequent === 'function') ? antiConsequent() : antiConsequent;
        }
    }
}

// export type PredicatePair<T> = [ Predicate, T ];
// export function PredicateSwitch<T>(pairs: PredicatePair<T>[]): T | void;
// export function PredicateSwitch<T>(pairs: PredicatePair<T>[], defaultValue: T): T;
// export function PredicateSwitch<T>(pairs: PredicatePair<T>[], defaultValue?: T): T | void {
export function conditionalSwitch(pairs, defaultValue = SYM_AWAITING_STRIP) {
    for (let [ predicate, value ] of pairs) {
        const result = evalPredicate(predicate);
        if (result) return value;
    }
    return defaultValue;
}

// export function isDev(): boolean;
// export function isDev<C>(consequent: Consequent<C>): C | SYM_AWAITING_STRIP;
// export function isDev<C, A>(consequent: Consequent<C>, antiConsequent: Consequent<A>): C | A;
// export function isDev<C, A>(consequent?: Consequent<C>, antiConsequent?: Consequent<A>): C | A | SYM_AWAITING_STRIP | boolean {
export function isDev(consequent, antiConsequent) {
    return condition(() => (MODE === 'development'), consequent, antiConsequent);
}


// export function isProd(): boolean;
// export function isProd<C>(consequent: Consequent<C>): C | SYM_AWAITING_STRIP;
// export function isProd<C, A>(consequent: Consequent<C>, antiConsequent: Consequent<A>): C | A;
// export function isProd<C, A>(consequent?: Consequent<C>, antiConsequent?: Consequent<A>): C | A | SYM_AWAITING_STRIP | boolean {
export function isProd(consequent, antiConsequent) {
    return condition(() => (MODE === 'production'), consequent, antiConsequent);
}


// export function isDebug(): boolean;
// export function isDebug(C>(consequent: Consequent<C>): C | SYM_AWAITING_STRIP;
// export function isDebug(C, A>(consequent: Consequent<C>, antiConsequent: Consequent<A>): C | A;
// export function isDebug(C, A>(consequent?: Consequent<C>, antiConsequent?: Consequent<A>): C | A | SYM_AWAITING_STRIP | boolean {
export function isDebug(consequent, antiConsequent) {
    return condition(() => DEBUGGER_PRESENT, consequent, antiConsequent);
}

// export function strip<T>(source: T[]): T[];
// export function strip<T>(source: T[], deep: boolean): T[];
// export function strip<T extends {}>(source: T): Partial<T>;
// export function strip<T extends {}>(source: T, deep: boolean): Partial<T>;
// export function strip<T>(source: T | T[], deep: boolean = true): Partial<T> | T[] {
export function strip(source, deep = true) {
    const isPoJo = _.isPlainObject(source);
    return _.reduce(source, (reduction, v, k) => {
        if (v !== SYM_AWAITING_STRIP) {
            if (deep && (_.isPlainObject(v) || _.isArray(v))) {
                if (isPoJo) reduction[ k ] = strip(v, deep);
                else reduction.push(strip(v, deep));
            } else {
                if (isPoJo) reduction[ k ] = v;
                else reduction.push(v);
            }
        }
        return reduction;
    }, isPoJo ? {} : []);
}

// export function omitEmpty<T>(source: T[]): T[] | SYM_AWAITING_STRIP;
// export function omitEmpty<T extends {}>(source: T): Partial<T> | SYM_AWAITING_STRIP;
// export function omitEmpty<T>(source: T | T[]): Partial<T> | T[] | SYM_AWAITING_STRIP {
export function omitEmpty(source) {
    if (_.isPlainObject(source) && Object.keys(source).length === 0) return SYM_AWAITING_STRIP;
    else if (_.isArray(source) && source.length === 0) return SYM_AWAITING_STRIP;
    else return source;
}

// module.exports.default = module.exports = {
//     isDev, isProd, isDebug, conditionalSwitch, condition, strip, omitEmpty
// };


export const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));