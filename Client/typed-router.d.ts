/* eslint-disable */
/* prettier-ignore */
// @ts-nocheck
// Generated by unplugin-vue-router. ‼️ DO NOT MODIFY THIS FILE ‼️
// It's recommended to commit this file.
// Make sure to add this file to your tsconfig.json file as an "includes" or "files" entry.

declare module 'vue-router/auto-routes' {
  import type {
    RouteRecordInfo,
    ParamValue,
    ParamValueOneOrMore,
    ParamValueZeroOrMore,
    ParamValueZeroOrOne,
  } from 'vue-router'

  /**
   * Route name map generated by unplugin-vue-router
   */
  export interface RouteNamedMap {
    '/': RouteRecordInfo<'/', '/', Record<never, never>, Record<never, never>>,
    '/Abilities/': RouteRecordInfo<'/Abilities/', '/Abilities', Record<never, never>, Record<never, never>>,
    '/Abilities/[identifier]': RouteRecordInfo<'/Abilities/[identifier]', '/Abilities/:identifier', { identifier: ParamValue<true> }, { identifier: ParamValue<false> }>,
    '/Moves/': RouteRecordInfo<'/Moves/', '/Moves', Record<never, never>, Record<never, never>>,
    '/Moves/[identifier]': RouteRecordInfo<'/Moves/[identifier]', '/Moves/:identifier', { identifier: ParamValue<true> }, { identifier: ParamValue<false> }>,
    '/Pokemon/': RouteRecordInfo<'/Pokemon/', '/Pokemon', Record<never, never>, Record<never, never>>,
    '/Pokemon/[identifier]': RouteRecordInfo<'/Pokemon/[identifier]', '/Pokemon/:identifier', { identifier: ParamValue<true> }, { identifier: ParamValue<false> }>,
  }
}
