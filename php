#!/bin/sh
docker compose exec -e XDEBUG_MODE="${1:-develop}" php bash
