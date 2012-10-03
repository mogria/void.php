
all: help

#############################
# Running the PHPUnit tests #
#############################
t:
	@phpunit --bootstrap test/test_bootstrap.php test/

tst: t

test: t

#####################################
# Help dialog when no command given #
#####################################
help:
	@echo "This is the Makefile for the void.php framework."
	@echo "The following commands are available: "
	@echo ""
	@echo "  - test (t, tst)              Run the PHPUnit tests"
	#@echo "  - generate (g, gen)          Generate Files"
	#@echo "  - clean (c, cln)             Remove Application specific files"
	#@echo "  - console (shell, cnsl)      Start a simple PHP Shell, booted in void.php"
	@echo ""
	@echo "execute them using:"
	@echo "$ make <command> [<args>] [...]"
	@echo ""
