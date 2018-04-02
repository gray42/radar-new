# Arbiter.Arbiter

- use `Psr\Container\ContainerInterface` in `ActionHandler`
- rename `ActionHandler::handle` to avoid psr15 confusion
- use psr15-like signature on `ActionHandler`

# Radar.Action

- Basically adds psr7 + 15 to Arbiter.
- Uses `Aura\Payload_Interface\ReadablePayloadInterface`
- requires psr7 + 15, arbiter, and aura/payload-interface

# Radar.Adr

- wires up all the components including relay, et al.

# Radar.Router

- Integrates Aura/Router with Radar/Actoin

